<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Entity\User;
use App\Enum\OrderStatusEnum;
use App\Repository\OrderDetailRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;

readonly class CartService
{
    public function __construct(
        private OrderRepository $orderRepository,
        private OrderDetailRepository $orderDetailRepository,
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
        $detail = $this->orderDetailRepository->findOneByOrderAndProduct($order, $product);

        if ($detail) {
            if ($quantity <= 0) {
                $order->removeOrderDetail($detail);
            } else {
                $detail->setQuantity($quantity);
                $detail->setUpdatedAt(new \DateTimeImmutable());
            }
        } elseif ($quantity > 0) {
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

    public function getProductQuantityInCart(Order $order, Product $product): int
    {
        $detail = $this->orderDetailRepository->findOneByOrderAndProduct($order, $product);

        return $detail ? $detail->getQuantity() : 0;
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

    /**
     * @throws RandomException
     */
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

    /**
     * @throws RandomException
     */
    public function generateOrderNumber(Order $order): string
    {
        return sprintf('GG-%s-%s',
            (new \DateTimeImmutable())->format('Ymd'),
            strtoupper(bin2hex(random_bytes(3)))
        );
    }
}
