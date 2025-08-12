'use client'

import React, { useState } from 'react';
import styles from './cart.module.css';
import { Hamburger } from 'lucide-react';

interface Props {
  items: any[];
  quantities: Record<string, number>;
  handleQuantityChange: (id: number, value: string) => void;
  updateItem: (id: number, qty: number) => void;
  removeItem: (id: number) => void;
  isCartLoading: boolean;
  totalPrice: number;
  itemCount: number;
}

const CartItemsSection: React.FC<Props> = ({
  items, quantities, handleQuantityChange,
  updateItem, removeItem, isCartLoading, totalPrice, itemCount
}) => {
  const [imageError, setImageError] = useState(false)

  const handleLoadError = () => {
    setImageError(true)
  }

   return (
    <div className={styles.cartSection}>
      <h2 className={styles.header}>Your Cart ({itemCount} items)</h2>
      <ul className={styles.itemList}>
        {items.map((item) => (
          <li key={item.order_number} className={styles.item}>
            {imageError || !item?.gallery?.images[0].path 
              ? <div className={styles.itemIcon}><Hamburger size={30}/></div>
              : <img src={item?.gallery?.images[0].path} alt={item.name} className={styles.itemImage} onError={handleLoadError}/>}
            <div className={styles.itemDetails}>
              <span>{item.name} - ${item.price.toFixed(2)}</span>
              <div className={styles.quantityWrapper}>
                <label className={styles.quantityLabel}>Quantity: </label>
                <input
                  type="number"
                  value={quantities[item.id] || ''}
                  onChange={(e) => handleQuantityChange(item.id, e.target.value)}
                  min="1"
                  className={styles.quantityInput}
                  disabled={isCartLoading}
                />
                <button
                  onClick={() => updateItem(item.id, quantities[item.id])}
                  disabled={isCartLoading || quantities[item.id] === item.quantity}
                  className={`${styles.button} ${styles.updateButton}`}
                >
                  Update
                </button>
                <button
                  onClick={() => removeItem(item.id)}
                  disabled={isCartLoading}
                  className={`${styles.button} ${styles.removeButton}`}
                >
                  Remove
                </button>
              </div>
            </div>
          </li>
        ))}
      </ul>
      <h3 className={styles.total}>Total: ${totalPrice.toFixed(2)}</h3>
    </div>
  );
};

export default CartItemsSection;
