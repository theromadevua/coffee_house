import React from 'react';
import styles from '../../../shared/styles/form.module.css';
import { useOrderForm } from '../../hooks/useOrderForm';
import OrderFieldInput from './OrderFieldsInput';
import { OrderItemInput } from './OrderItemInput';
import { IOrder } from '../../../shared/interfaces/order/IOrder';
import { OrderStatusEnum } from '../../../shared/enums/order/OrderStatusEnum';

interface OrderFormProps {
  open: boolean;
  onClose: () => void;
  orderToEdit: IOrder | null;
}

export const OrderForm: React.FC<OrderFormProps> = ({
  open,
  onClose,
  orderToEdit,
}) => {
  const {
    deliveryAddress,
    contactPhone,
    deliveryTime,
    status,
    items,
    isEditMode,
    setDeliveryAddress,
    setContactPhone,
    setDeliveryTime,
    setStatus,
    handleItemChange,
    addItem,
    removeItem,
    handleSubmit,
  } = useOrderForm({ orderToEdit, onClose });

  if (!open) return null;

  const statusOptions = Object.values(OrderStatusEnum).map((s) => ({
    value: s,
    label: s.charAt(0).toUpperCase() + s.slice(1),
  }));

  return (
    <div className={styles.backdrop}>
      <div className={styles.dialog}>
        <h2 className={styles.title}>
          {isEditMode ? 'Edit Order' : 'Create Order'}
        </h2>

        <div className={styles.content}>
          <form onSubmit={handleSubmit} className={styles.form}>
            <OrderFieldInput
              status={status}
              deliveryAddress={deliveryAddress}
              contactPhone={contactPhone}
              deliveryTime={deliveryTime}
              setDeliveryAddress={setDeliveryAddress}
              setContactPhone={setContactPhone}
              setDeliveryTime={setDeliveryTime}
              setStatus={setStatus}
              statusOptions={statusOptions}
              isEditMode={isEditMode}
            />

            <div className={styles.formGroup}>
              <label className={styles.subTitle}>Order Items</label>
              {items.map((item, index) => (
                <OrderItemInput
                  key={index}
                  item={item}
                  index={index}
                  handleItemChange={handleItemChange}
                  removeItem={removeItem}
                  showRemoveButton={items.length > 1}
                />
              ))}
              <button
                type="button"
                className={`${styles.button} ${styles.primaryButton}`}
                onClick={addItem}
              >
                Add Item
              </button>
            </div>
          </form>
        </div>

        <div className={styles.actions}>
          <button
            type="button"
            className={styles.button}
            onClick={onClose}
          >
            Cancel
          </button>
          <button
            type="submit"
            className={`${styles.button} ${styles.primaryButton}`}
            onClick={handleSubmit}
          >
            {isEditMode ? 'Save Changes' : 'Create Order'}
          </button>
        </div>
      </div>
    </div>
  );
};
