import React from 'react';
import styles from './cart.module.css';

interface Props {
  deliveryAddress: string;
  setDeliveryAddress: (val: string) => void;
  contactPhone: string;
  setContactPhone: (val: string) => void;
  deliveryTime: string;
  setDeliveryTime: (val: string) => void;
  handlePlaceOrder: (e: React.FormEvent) => void;
  formError: string;
  orderError: string | null;
  isOrderLoading: boolean;
}

const CheckoutForm: React.FC<Props> = ({
  deliveryAddress,
  setDeliveryAddress,
  contactPhone,
  setContactPhone,
  deliveryTime,
  setDeliveryTime,
  handlePlaceOrder,
  formError,
  orderError,
  isOrderLoading,
}) => {
  return (
    <div className={styles.checkoutSection}>
      <h2 className={styles.header}>Checkout</h2>
      <form onSubmit={handlePlaceOrder} className={styles.form}>
        <div className={styles.formGroup}>
          <label htmlFor="address" className={styles.label}>
            Delivery Address
          </label>
          <textarea
            id="address"
            value={deliveryAddress}
            onChange={(e) => setDeliveryAddress(e.target.value)}
            className={styles.textarea}
            required
            disabled={isOrderLoading}
            placeholder="Enter your full delivery address"
          />
        </div>
        <div className={styles.formGroup}>
          <label htmlFor="phone" className={styles.label}>
            Contact Phone
          </label>
          <input
            id="phone"
            type="tel"
            value={contactPhone}
            onChange={(e) => setContactPhone(e.target.value)}
            className={styles.input}
            required
            disabled={isOrderLoading}
            placeholder="e.g., +1234567890"
          />
        </div>
        <div className={styles.formGroup}>
          <label htmlFor="time" className={styles.label}>
            Preferred Delivery Time (Optional)
          </label>
          <input
            id="time"
            type="datetime-local"
            value={deliveryTime}
            onChange={(e) => setDeliveryTime(e.target.value)}
            className={styles.input}
            disabled={isOrderLoading}
          />
        </div>
        {formError && (
          <div className={styles.error} role="alert">
            {formError}
          </div>
        )}
        {orderError && (
          <div className={styles.error} role="alert">
            Error placing order: {orderError}
          </div>
        )}
        <button
          type="submit"
          onClick={handlePlaceOrder}
          className={`${styles.button} ${styles.checkoutButton}`}
          disabled={isOrderLoading}
        >
          {isOrderLoading ? 'Placing Order...' : 'Place Order (PayPal)'}
        </button>
      </form>
    </div>
  );
};

export default CheckoutForm;