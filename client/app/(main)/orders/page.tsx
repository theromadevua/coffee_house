'use client';

import React, { useEffect } from 'react';
import Link from 'next/link';
import { useOrderStore } from '@/store/orderStore';
import styles from './orderPage.module.css';
import useAuthStore from '@/store/authStore';
import { useRouter } from 'next/navigation';

const OrderHistoryView = () => {
  const { orders, isLoading, error, fetchOrders } = useOrderStore();
  const {user, isLoading: isUserLoading, isInitialized} = useAuthStore()
  const router = useRouter()

  useEffect(() => {
    if (!user && !isUserLoading && isInitialized) {
      router.push('/auth/login');
    }
  }, [user, isUserLoading, isInitialized, router]);

  useEffect(() => {
    fetchOrders();
  }, [fetchOrders]);

  if (isLoading) {
    return <div className={styles.container}>
      <div className={styles.loading}>Loading your orders...</div>
    </div>;
  }

  if (error) {
    return <div className={styles.container}>
      <div className={styles.error}>Error fetching orders: {error}</div>
    </div>;
  }

  if (orders.length === 0) {
    return <div className={styles.container}>
      <div className={styles.empty}>You haven't placed any orders yet.</div>
    </div>;
  }

  return (
    <div className={styles.container}>
      <h2 className={styles.header}>My Orders</h2>
      <ul className={styles.orderList}>
        {[...orders].reverse().map((order) => (
          <li key={order.order_number}>
            <Link href={`/orders/${order.order_number}`} className={styles.orderItem}>
              <div className={styles.orderDetails}>
                <span className={styles.orderId}>Order #{order.order_number}</span>
                <span className={styles.orderDate}>Placed on: {order.created_at}</span>
                 <span className={`${styles.orderStatus} ${styles[`status_${order.status}`]}`}>
                  {order.status}
                </span>
              </div>
              <div className={styles.orderTotal}>
                ${order.total_amount}
              </div>
            </Link>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default OrderHistoryView;