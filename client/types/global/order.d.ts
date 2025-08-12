export {};

declare global {
  enum OrderStatus {
    NEW = 'new',
    PROCESSING = 'processing',
    READY = 'ready',
    DELIVERED = 'delivered',
    CANCELLED = 'cancelled'
  }

  interface OrderItem {
    id: number;
    // quantity: number;
    price: number;
    pivot?: any;
  }

  interface Order {
    id: number;
    userId: number;
    tableId?: number | null;
    reservationTime?: string | null;
    delivery_address?: string;
    contact_phone?: string;
    total_amount?: number;
    created_at?: string;
    status: OrderStatus;
    items: OrderItem[];
    order_number: number;
  }

  interface CreateOrderData {
    delivery_address: string;
    tableId?: number | null;
    contact_phone: string;
    delivery_time: string | null;
    items: { dish_id: number; quantity: number, price: number }[];
    status?: string;
    total_amount?: number;
  }

  type UpdateOrderData = Partial<
    Omit<CreateOrderData, 'items'> & {
      status: OrderStatus;
      items: Array<{
        dish_id: number;
        quantity: number;
      }>;
    }
  >;
}
