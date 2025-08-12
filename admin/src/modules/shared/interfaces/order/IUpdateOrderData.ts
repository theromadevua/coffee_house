import { OrderStatusEnum } from "../../enums/order/OrderStatusEnum";

export interface IUpdateOrderData {
    delivery_address?: string;
    contact_phone?: string;
    delivery_time?: string;
    status?: OrderStatusEnum;
    items?: { dish_id: number; quantity: number }[]; 
}