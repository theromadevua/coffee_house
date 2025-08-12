import { create } from 'zustand';
import { OrderService } from '../services/orderService';
import { handleStoreRequest } from './utils/ApiHandler';
import { ICreateOrderData } from '../modules/shared/interfaces/order/ICreateOrderData';
import { IOrder } from '../modules/shared/interfaces/order/IOrder';
import { IUpdateOrderData } from '../modules/shared/interfaces/order/IUpdateOrderData';

interface OrderState {
  orders: IOrder[];
  currentOrder: IOrder | null; 
  isLoading: boolean;
  error: string | null;
  ordersStatistics: any,
  fetchOrders: () => Promise<void>;
  fetchAllOrders: () => Promise<void>;
  findOrder: (id: number) => Promise<void>;
  createOrder: (orderData: ICreateOrderData) => Promise<IOrder | undefined>;
  updateOrder: (id: number, orderData: IUpdateOrderData) => Promise<void>;
  deleteOrder: (id: number) => Promise<void>;
  downloadOrderPDF: (order: IOrder) => void;
  getOrdersStatistics: () => Promise<void>;
}

export const useOrderStore = create<OrderState>((set, get) => ({
  orders: [],
  currentOrder: null,
  isLoading: false,
  error: null,
  ordersStatistics: null,

  fetchOrders: async () => {
    await handleStoreRequest(set, get, () => OrderService.fetchOrders(), {
      onSuccess: (data) => ({ orders: data }),
    });
  },

  findOrder: async (id: number) => {
    set({ currentOrder: null }); 
    await handleStoreRequest(set, get, () => OrderService.findOrder(id), {
      onSuccess: (data) => ({ currentOrder: data }),
    });
  },

  createOrder: async (orderData: ICreateOrderData) => {
    let newOrder: IOrder | undefined;
    
    await handleStoreRequest(set, get, () => OrderService.createOrder(orderData), {
      onSuccess: (data, state) => {
        newOrder = data; 
        return {
          orders: [data, ...state.orders], 
        };
      },
    });
    return newOrder; 
  },

  updateOrder: async (id, orderData) => {
    console.log(orderData)
    await handleStoreRequest(set, get, () => OrderService.updateOrder(id, orderData), {
      onSuccess: (updatedOrder, state) => {
        return {
          orders: state.orders.map((o) =>
            o.id === id ? { ...o, ...updatedOrder } : o
          ),
          currentOrder: state.currentOrder?.id === id ? { ...state.currentOrder, ...updatedOrder } : state.currentOrder,
        };
      },
    });
  },

  deleteOrder: async (id) => {
    await handleStoreRequest(set, get, () => OrderService.deleteOrder(id), {
      onSuccess: (_, state) => ({
        orders: state.orders.filter((o) => o.id !== id),
        currentOrder: state.currentOrder?.id === id ? null : state.currentOrder,
      }),
    });
  },

  fetchAllOrders: async () => {
    await handleStoreRequest(set, get, () => OrderService.fetchAllOrders(), {
      onSuccess: (data) => ({
        orders: data
      })
    });
  },

  getOrdersStatistics: async () => {
    await handleStoreRequest(set, get, () => OrderService.getOrdersStatistics(), {
      onSuccess: (data) => ({
        ordersStatistics: data
      })
    });
  },

  downloadOrderPDF: async (order: IOrder) => {
    const response: any = await fetch(`http://coffee.test/api/orders/download/${order.id}`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('accessToken')}`, 
      },
    });

    const blob = await response.blob();
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `order_${order.id}.pdf`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(url);
  }


  // downloadOrderPDF: (order: Order) => {
  //   const doc = new jsPDF();
  
  //   doc.setFontSize(20);
  //   doc.text('Order #' + order.id, 20, 20);
  
  //   doc.setFontSize(12);
  //   let yPosition = 40;
  
  //   doc.text('Order Information:', 20, yPosition);
  //   yPosition += 10;
  
  //   if (order.delivery_address) {
  //     doc.text('Delivery Address: ' + order.delivery_address, 20, yPosition);
  //     yPosition += 7;
  //   }
  
  //   if (order.contact_phone) {
  //     doc.text('Phone: ' + order.contact_phone, 20, yPosition);
  //     yPosition += 7;
  //   }
  
  //   if (order.tableId) {
  //     doc.text('Table: ' + order.tableId, 20, yPosition);
  //     yPosition += 7;
  //   }
  
  //   if (order.created_at) {
  //     const date = new Date(order.created_at);
  //     doc.text('Created At: ' + date.toLocaleString('en-GB'), 20, yPosition);
  //     yPosition += 7;
  //   }
  
  //   doc.text('Status: ' + order.status, 20, yPosition);
  //   yPosition += 15;
  
  //   const tableData = order.items.map(item => [
  //     item.id,
  //     item?.pivot.quantity,
  //     item.price,
  //     item?.pivot.quantity * item.price
  //   ]);
  
  //   autoTable(doc, {
  //     head: [['Item ID', 'Quantity', 'Unit Price', 'Total']],
  //     body: tableData,
  //     startY: yPosition,
  //     theme: 'grid',
  //     headStyles: { fillColor: [66, 139, 202] },
  //     styles: { fontSize: 10 }
  //   });
  
  //   const finalY = (doc as any).lastAutoTable.finalY + 10;
  //   doc.setFontSize(14);
  //   const totalAmount = order.total_amount || order.items.reduce((acc, item) => acc + (item.price * item?.pivot?.quantity), 0);
  //   doc.text('Total Amount: ' + totalAmount, 20, finalY);
  
  //   doc.save(`order-${order.id}.pdf`);
  // }  

}));