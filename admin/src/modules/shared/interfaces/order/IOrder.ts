import { OrderStatusEnum } from "../../enums/order/OrderStatusEnum";
import { IOrderItem } from "./IOrderItem";

export interface IOrder {
    id: number;
    userId: number;
    tableId?: number | null;
    delivery_time?: string | null;
    delivery_address?: string;
    contact_phone?: string;
    total_amount?: number;
    created_at?: string;
    status: OrderStatusEnum;
    items: IOrderItem[]; 
  }