'use client';

import React, { useEffect } from 'react';
import styles from './cart.module.css';
import { useCartLogic } from './hooks/useCartLogic';
import CartItemsSection from './components/CartItemsSection/CartItemsSection';
import CheckoutForm from './components/CheckoutForm/CheckoutForm';
import useAuthStore from '@/store/authStore';
import { useRouter } from 'next/navigation';
import Footer from '../components/Footer/Footer';

const CartPage = () => {
  const logic = useCartLogic();
  const {user, isLoading, isInitialized} = useAuthStore()
  const router = useRouter()

  useEffect(() => {
    if (!user && !isLoading && isInitialized) {
      router.push('/auth/login');
    }
  }, [user, isLoading, isInitialized, router]);

  if (logic.isCartLoading && logic.items.length === 0) {
    return <div className={styles.container}><div className={styles.loading}>Loading your cart...</div></div>;
  }

  if (logic.cartError) {
    return <div className={styles.container}>
      <div className={styles.error}>Error loading cart: {logic.cartError}</div>
    </div>;
  }

  if (logic.items.length === 0 && !logic.isCartLoading) {
    return (
      <div className={styles.container}>
        <div className={styles.empty}>Your cart is empty. Go add some delicious food!</div>
      </div>
    );
  }

  return (
    <div className={styles.container}>
      <CartItemsSection
        items={logic.items}
        quantities={logic.quantities}
        handleQuantityChange={logic.handleQuantityChange}
        updateItem={logic.updateItem}
        removeItem={logic.removeItem}
        isCartLoading={logic.isCartLoading}
        totalPrice={logic.totalPrice}
        itemCount={logic.itemCount}
      />

      {logic.items.length > 0 && (
        <CheckoutForm
          deliveryAddress={logic.deliveryAddress}
          setDeliveryAddress={logic.setDeliveryAddress}
          contactPhone={logic.contactPhone}
          setContactPhone={logic.setContactPhone}
          deliveryTime={logic.deliveryTime}
          setDeliveryTime={logic.setDeliveryTime}
          handlePlaceOrder={logic.handlePlaceOrder}
          formError={logic.formError}
          orderError={logic.orderError}
          isOrderLoading={logic.isOrderLoading}
        />
      )}
    </div>
  );
};

export default CartPage;
