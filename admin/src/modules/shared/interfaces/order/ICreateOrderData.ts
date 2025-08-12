import { OrderStatusEnum } from "../../enums/order/OrderStatusEnum";

export interface ICreateOrderData {
    delivery_address: string;
    contact_phone: string;
    delivery_time?: string;
    total_amount: number;
    items: { dish_id: number; quantity: number }[]; 
    status?: OrderStatusEnum;
  }