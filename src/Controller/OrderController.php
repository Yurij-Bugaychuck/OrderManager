<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Orders;
use App\Entity\OrserStatuses;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Component\Validator\Constraints\Date;

class OrderController extends AbstractController
{
    #[Route('/', name: 'index', methods: ["GET"])]
    public function home(): RedirectResponse{
        return $this->redirectToRoute("order");
    }
    #[Route('/order', name: 'order', methods: ["GET"])]
    public function index(): Response
    {
        $ordersDB = $this->getDoctrine()->getRepository(Orders::class)->findBy([], ['order_created_at'=>'DESC']);
        
        $statusOrders = [];

        $statusOrders[] =[
                'Name' => 'Все',
                'orders' => $this->orderDTO($ordersDB),
                'count' => count($ordersDB)
            ];
        
        $Statuses = $this->getDoctrine()->getRepository(OrserStatuses::class)->findBy([], ['id' => 'ASC']);

        foreach($Statuses as $status){
            $orders = $this->orderDTO($this->getDoctrine()->getRepository(Orders::class)->findBy(['statusId' => $status->getId()], ['order_created_at'=>'DESC']));
            $statusOrders[] = [
                'Name' => $status->getName(),
                'orders' => $orders,
                'count' => count($orders)
            ];
        }
        
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
            'ordersList' => $statusOrders
        ]);
    }

    

    private function orderDTO($ordersDB){
        $orders = [];
        foreach($ordersDB as $orderDB){
            
            $order =[
                'id'    =>  $orderDB->getId(),
                'clientName'   =>  $orderDB->getClientName(),
                'clientPhone'   =>  $orderDB->getClientPhone(),
                'order'   =>  $orderDB->getOrder(),
                'Notes'   =>  $orderDB->getNotes(),
                'price'   =>  $orderDB->getPrice(),
                'paid'   =>  $orderDB->getPaid(),
                'shippingDetails'   =>  $orderDB->getShippingDetails(),
                'orderStatusId'   =>  intval($orderDB->getStatusId()->getId()),
                'orderStatus'   =>  $orderDB->getStatusId()->getName(),
                'createdAt'   =>  date_format($orderDB->getOrderCreatedAt(), "d.m.Y H:i"),
                'instagram'   =>  $orderDB->getInstagram(),
                'paid'   =>  $orderDB->getPaid(),
                'shipping'   =>  $orderDB->getShipping(),
                'ttn'   =>  $orderDB->getTtn()
            ];
            
            $orders[] = $order;
        }

        return $orders;
    }

    #[Route('/order/ttn/{id}', name: 'addTtn', methods: ["GET"])]
    public function addTtn(int $id, Request $req, EntityManagerInterface $entityManager) : Response {
        $order = $entityManager->getRepository(Orders::class)->find($id);

        $templateValues = [];

        $templateValues['ttn'] = $order->getTtn();
        return $this->render("order/ttn.twig", [
            'controller_name' => 'OrderController',
            'id' => $id,
            'templateValues' => $templateValues
        ]);
    }

    #[Route('/order/ttn/{id}', name: 'updateTtn', methods: ["PUT"])]
    public function updateTtn(int $id, Request $req, EntityManagerInterface $entityManager) : RedirectResponse {

        $order = $entityManager->getRepository(Orders::class)->find($id);
        $orderStat = $entityManager->getRepository(OrserStatuses::class)->find(3);

        if (!$order) {
            throw $this->createNotFoundException(
                'No order found for id '.$id
            );
        };

        $ttn = "";
        if ($req->request->get('ttn') != null){
            $ttn = $req->request->get('ttn');
        };


        $order->setTtn($ttn);
        $order->setStatusId($orderStat);
        $entityManager->flush();
        
        return $this->redirectToRoute('order');
    }

    #[Route('/order/success/{id}', name: 'success', methods: ["GET"])]
    public function success(int $id, Request $req, EntityManagerInterface $entityManager) : RedirectResponse {

        $order = $entityManager->getRepository(Orders::class)->find($id);
        $orderStat = $entityManager->getRepository(OrserStatuses::class)->find(4);

        if (!$order) {
            throw $this->createNotFoundException(
                'No order found for id '.$id
            );
        };

        $order->setStatusId($orderStat);
        $entityManager->flush();
        
        return $this->redirectToRoute('order');
    }

    #[Route('/order/add', name: 'pageAddOrder', methods: ["GET"])]
    public function addOrder(Request $req) :Response{
        $form = ['action' => '/order', 'method' => "POST", '_method' => "NONE"];
        $Statuses = $this->getDoctrine()->getRepository(OrserStatuses::class)->findBy([], ['id' => 'ASC']);
        
        $resStatuses = [];
        foreach($Statuses as $status){
            $resStatuses[] = ['id' => $status->getId(), 'name' => $status->getName()];
        };
        

        return $this->render('order/addorder.html.twig', [
            'controller_name' => 'OrderController',
            'statuses' => $resStatuses,
            'form' => $form,
            'templateValues' => $this->genTemplateValues()
        ]);
    }

    #[Route('/order/update/{id}', name: 'pageUpdateOrder', methods: ["GET"])]
    public function vupdateOrder(int $id, Request $req) : Response{
        $form = ['action' => '/order/update/' . $id, 'method' => "POST", '_method' => "PUT"];
        $Statuses = $this->getDoctrine()->getRepository(OrserStatuses::class)->findBy([], ['id' => 'ASC']);
        
        $resStatuses = [];
        foreach($Statuses as $status){
            $resStatuses[] = ['id' => $status->getId(), 'name' => $status->getName()];
        };

        $order = $this->getDoctrine()->getRepository(Orders::class)->find($id);
        

        return $this->render('order/addorder.html.twig', [
            'controller_name' => 'OrderController',
            'statuses' => $resStatuses,
            'templateValues' => $this->genTemplateValues($order),
            'form' => $form
        ]);
    }

    #[Route('/order', name: 'newOrder', methods: ["POST"])]
    public function newOrder(Request $req, EntityManagerInterface $entityManager): RedirectResponse
    {
        
        $order = new Orders();

        if ($req->request->get("clientName") != null) {
            $order->setClientName($req->request->get("clientName"));
        }  

        if ($req->request->get("clientPhone") != null) {
            $order->setClientPhone($req->request->get("clientPhone"));
        }  

        if ($req->request->get("clientInstagram") != null) {
            $order->setInstagram($req->request->get("clientInstagram"));
        }  

        if ($req->request->get("clientPaid") != null) {
            $order->setPaid(intval($req->request->get("clientPaid")));
        }  

        if ($req->request->get("orderPrice") != null) {
            $order->setPrice(intval($req->request->get("orderPrice")));
        }  

        if ($req->request->get("orderStatus") != null) {
            $status = $this->getDoctrine()->getRepository(OrserStatuses::class)->findOneBy(['id' => intval($req->request->get("orderStatus"))]);
            $order->setStatusId($status);
        }  

        if ($req->request->get("order") != null) {
            $order->setOrder($req->request->get("order"));
        }  

        if ($req->request->get("notes") != null) {
            $order->setNotes($req->request->get("notes"));
        }  


        if ($req->request->get("orderdate") != null) {
            $tmp = new DateTime($req->request->get("orderdate"));
            $order->setOrderCreatedAt($tmp);
        } else{
            $tmp = new DateTime();
            $order->setOrderCreatedAt($tmp);
        }

        if ($req->request->get("shipping") != null) {
            $order->setShipping($req->request->get("shipping"));
        }  

        if ($req->request->get("shippingDetails") != null) {
            $order->setShippingDetails($req->request->get("shippingDetails"));
        }  

        

        

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->redirectToRoute('order');
    }

    #[Route('/order/update/{id}', name: 'updateOrder', methods: ["PUT"])]
    public function updateOrder(int $id, Request $req, EntityManagerInterface $entityManager): RedirectResponse
    {
        $order = $entityManager->getRepository(Orders::class)->find($id);

        if (!$order) {
            throw $this->createNotFoundException(
                'No order found for id '.$id
            );
        }

        if ($req->request->get("clientName") != null) {
            $order->setClientName($req->request->get("clientName"));
        }  

        if ($req->request->get("clientPhone") != null) {
            $order->setClientPhone($req->request->get("clientPhone"));
        }  

        if ($req->request->get("clientInstagram") != null) {
            $order->setInstagram($req->request->get("clientInstagram"));
        }  

        if ($req->request->get("clientPaid") != null) {
            $order->setPaid(intval($req->request->get("clientPaid")));
        }  

        if ($req->request->get("orderPrice") != null) {
            $order->setPrice(intval($req->request->get("orderPrice")));
        }  

        if ($req->request->get("orderStatus") != null) {
            $status = $this->getDoctrine()->getRepository(OrserStatuses::class)->findOneBy(['id' => intval($req->request->get("orderStatus"))]);
            $order->setStatusId($status);
        }  

        if ($req->request->get("order") != null) {
            $order->setOrder($req->request->get("order"));
        }  

        if ($req->request->get("notes") != null) {
            $order->setNotes($req->request->get("notes"));
        }  


        if ($req->request->get("orderdate") != null) {
            $tmp = new DateTime($req->request->get("orderdate"));
            $order->setOrderCreatedAt($tmp);
        } else{
            $tmp = new DateTime();
            $order->setOrderCreatedAt($tmp);
        }

        if ($req->request->get("shipping") != null) {
            $order->setShipping($req->request->get("shipping"));
        }  

        if ($req->request->get("shippingDetails") != null) {
            $order->setShippingDetails($req->request->get("shippingDetails"));
        }  

        $entityManager->flush();

        return $this->redirectToRoute('order');
    }


    #[Route('/order/delete/{id}', name: 'PostOrder', methods: ["get"])]
    public function delete(int $id, Request $req, EntityManagerInterface $entityManager): RedirectResponse
    {

        $ordersDB = $entityManager->getRepository(Orders::class);
        
        $order = $ordersDB->find($id);

        $entityManager->remove($order);
        $entityManager->flush();
        
        return $this->redirectToRoute('order');
    }

    private function genTemplateValues(Orders $order = null) : array{
        
        $templateValues = [
            'clientName' => '',
            'clientPhone' => '',
            'clientInstagram' => '',
            'clientPaid' => '',
            'orderPrice' => '',
            'orderStatus' => '',
            'order' => '',
            'notes' => '',
            'orderdate' => '',
            'shipping' => '',
            'shippingDetails' => ''
        ];
        if ($order != null){
            $templateValues['clientName'] = $order->getClientName();
            $templateValues['clientPhone'] = $order->getClientPhone();
            $templateValues['clientInstagram'] = $order->getInstagram();
            $templateValues['clientPaid'] = $order->getPaid();
            $templateValues['orderPrice'] = $order->getPrice();
            $templateValues['orderStatus'] = intval($order->getStatusId()->getId());
            $templateValues['order'] = $order->getOrder();
            $templateValues['orderdate'] = $order->getOrderCreatedAt()->format("Y-m-d");
            $templateValues['notes'] = $order->getNotes();
            $templateValues['shipping'] = $order->getShipping();
            $templateValues['shippingDetails'] = $order->getShippingDetails();
        }
        return $templateValues;
    }
}
