'use client';

import React, { useEffect } from 'react';
import Link from 'next/link';
import { useParams } from 'next/navigation';
import { useOrderStore } from '@/store/orderStore';
import styles from './orderDetails.module.css';


const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const OrderDetailsView = () => {
  const {id: order_number} = useParams();
  const { currentOrder, isLoading, error, findOrder, downloadOrderPDF } = useOrderStore();
  

  useEffect(() => {
    if (order_number) {
      const orderId = parseInt(order_number as string, 10);
      if (!isNaN(orderId)) {
        findOrder(orderId);
      }
    }
  }, [order_number, findOrder]);

  if (isLoading) {
    return <div className={styles.loading}>Loading order details...</div>;
  }

  if (error) {
    return <div className={styles.error}>Error fetching order: {error}</div>;
  }

  if (!currentOrder) {
    return <div className={styles.empty}>Order not found.</div>;
  }

  return (
    <div className={styles.container}>
      <div className={styles.buttonsContainer}>
        <Link href="/orders" className={styles.backLink}>
          ← Back to All Orders
        </Link>
        <button onClick={() => downloadOrderPDF(currentOrder)} className={styles.downloadLink}>Download</button>
      </div>

      <div className={styles.header}>
        <h2>Order #{currentOrder.order_number}</h2>
        <span className={`${styles.orderStatus} ${styles[`status_${currentOrder.status}`]}`}>
          {currentOrder.status}
        </span>
      </div>

      <div className={styles.detailsGrid}>
        <div className={styles.detailItem}>
          <span className={styles.detailLabel}>Placed on</span>
          <span>{currentOrder.created_at ? formatDate(currentOrder.created_at) : 'N/A'}</span>
        </div>
        <div className={styles.detailItem}>
          <span className={styles.detailLabel}>Delivery Address</span>
          <span>{currentOrder.delivery_address || 'N/A'}</span>
        </div>
        <div className={styles.detailItem}>
          <span className={styles.detailLabel}>Contact Phone</span>
          <span>{currentOrder.contact_phone || 'N/A'}</span>
        </div>
        <div className={styles.detailItem}>
          <span className={styles.detailLabel}>Total Amount</span>
          <span className={styles.totalAmount}>${currentOrder.total_amount}</span>
        </div>
      </div>
      
      <h3 className={styles.itemsHeader}>Items in this Order</h3>
      <ul className={styles.itemList}>
        {currentOrder.items && currentOrder.items.map((item: any, index) => (
          <li key={index} className={styles.item}>
            <div className={styles.itemDetails}>
              <span className={styles.itemName}>{item?.name}</span>
              <span className={styles.itemQuantity}>Quantity: {item.pivot.quantity}</span>
            </div>
            <div className={styles.itemPrice}>
              ${(Number(item.price) * Number(item.pivot.quantity))}
              <span className={styles.itemPricePerUnit}>(${item.price} each)</span>
            </div>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default OrderDetailsView;