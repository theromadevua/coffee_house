
export interface IOrderItem {
    dish_id: number;
    price?: number; 
    pivot?: {
      quantity: number;
    };
    quantity: number;
  }