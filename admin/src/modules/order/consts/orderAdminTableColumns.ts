import { IOrder } from "../../shared/interfaces/order/IOrder";

export const orderAdminPageColumns = [
    { header: 'ID', accessor: (o: IOrder) => o.id },
    { header: 'Delivery Address', accessor: (o: IOrder) => o.delivery_address || 'N/A' },
    { header: 'Contact Phone', accessor: (o: IOrder) => o.contact_phone || 'N/A' },
    { header: 'Total Amount', accessor: (o: IOrder) => o.total_amount ? `$${o.total_amount}` : 'N/A' },
    { header: 'Status', accessor: (o: IOrder) => o.status },
    { header: 'Items', accessor: (o: IOrder) => `${o.items.length} item(s)` },
    {
      header: 'Delivery Time',
      accessor: (o: IOrder) =>
        o.delivery_time ? new Date(o.delivery_time).toISOString().slice(0, 16) : 'N/A'
    },
  ];