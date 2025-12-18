<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Entity\User;
use App\Enum\OrderStatusEnum;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class CartService
{
    public function __construct(
        private OrderRepository $orderRepository,
        private EntityManagerInterface $em
    ) {
    }

    public function getOrCreateCurrentCart(User $user): Order
    {
        $order = $this->getCurrentCart($user);

        if (!$order) {
            $order = new Order();
            $order->setUser($user);
            $order->setStatus(OrderStatusEnum::PENDING);
            $order->setCreatedAt(new \DateTimeImmutable());
            $order->setUpdatedAt(new \DateTimeImmutable());
            $order->setTotalPrice(0);

            $this->em->persist($order);
            $this->em->flush();
        }

        return $order;
    }

    public function getCurrentCart(User $user): ?Order
    {
        return $this->orderRepository->findOneBy([
            'user' => $user,
            'status' => OrderStatusEnum::PENDING,
        ]);
    }

    public function addOrUpdateProduct(Order $order, Product $product, int $quantity): void
    {
        $detailFound = false;

        foreach ($order->getOrderDetails() as $detail) {
            if ($detail->getProduct()->getId() === $product->getId()) {
                $detailFound = true;

                if ($quantity <= 0) {
                    $order->removeOrderDetail($detail);
                } else {
                    $detail->setQuantity($quantity);
                    $detail->setUpdatedAt(new \DateTimeImmutable());
                }

                break;
            }
        }

        if (!$detailFound && $quantity > 0) {
            $detail = new OrderDetail();
            $detail->setOrder($order);
            $detail->setProduct($product);
            $detail->setQuantity($quantity);
            $detail->setPriceAtOrder($product->getPrice());
            $detail->setCreatedAt(new \DateTimeImmutable());
            $detail->setUpdatedAt(new \DateTimeImmutable());

            $order->addOrderDetail($detail);
            $this->em->persist($detail);
        }

        $this->recalculateOrderTotal($order);

        $this->em->flush();
    }

    private function recalculateOrderTotal(Order $order): void
    {
        $total = 0;

        foreach ($order->getOrderDetails() as $detail) {
            $total += $detail->getQuantity() * $detail->getPriceAtOrder();
        }

        $order->setTotalPrice($total);
        $order->setUpdatedAt(new \DateTimeImmutable());
    }

    public function validateOrder(Order $order): void
    {
        if ($order->getOrderDetails()->isEmpty()) {
            throw new \LogicException('Impossible de valider une commande vide');
        }

        if ($order->getOrderNumber() === null) {
            $order->setOrderNumber($this->generateOrderNumber($order));
        }

        $order->setStatus(OrderStatusEnum::VALIDATED);
        $order->setValidatedAt(new \DateTimeImmutable());
        $order->setUpdatedAt(new \DateTimeImmutable());

        $this->em->flush();
    }

    public function generateOrderNumber(Order $order): string
    {
        return sprintf('GG-%06d', $order->getId());
    }
}
