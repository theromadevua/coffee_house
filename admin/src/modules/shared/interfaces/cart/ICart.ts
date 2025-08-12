
export interface ICart {
    user_id: number;
    items: Array<{
      id: number;
      name: string;
      price: number;
      imagePath: string;
      quantity: number;
      gallery: any;
      pivot: any;
    }>;
    total_price: number;
  }