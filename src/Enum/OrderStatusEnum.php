<?php

namespace App\Enum;

enum OrderStatusEnum: string
{
    case PENDING = 'pending';
    case VALIDATED = 'validated';
    case PREPARING = 'preparing';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'En cours de validation',
            self::VALIDATED => 'Commande validée',
            self::PREPARING => 'En cours de préparation',
            self::SHIPPED => 'Expédiée',
            self::DELIVERED => 'Livrée',
            self::CANCELLED => 'Annulée',
        };
    }
}
