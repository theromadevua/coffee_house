import React from 'react';
import styles from '../../../shared/styles/form.module.css';
import { useDishStore } from '../../../../store/dishStore';
import InputField from '../../../shared/components/formFields/InputField';
import { IOrderItem } from '../../../shared/interfaces/order/IOrderItem';

interface OrderItemInputProps {
  item: IOrderItem;
  index: number;
  handleItemChange: (index: number, field: 'dish_id' | 'quantity', value: number) => void;
  removeItem: (index: number) => void;
  showRemoveButton: boolean;
}

export const OrderItemInput: React.FC<OrderItemInputProps> = ({
  item,
  index,
  handleItemChange,
  removeItem,
  showRemoveButton,
}) => {
  const { dishes } = useDishStore();

  const dishOptions = [
    { value: '0', label: 'Select a dish' },
    ...dishes.map(dish => ({
      value: dish.id.toString(),
      label: `${dish.name} ($${dish.price})`,
    }))
  ];

  return (
    <div className={styles.itemRow}>
      <InputField
        id={`dish_id_${index}`}
        label="Dish"
        value={item.dish_id}
        onChange={(e: React.ChangeEvent<HTMLSelectElement>) => handleItemChange(index, 'dish_id', parseInt(e.target.value))}
        required
        type="select"
        options={dishOptions}
      />
      <InputField
        id={`quantity_${index}`}
        label="Quantity"
        value={item.quantity}
        onChange={(e: React.ChangeEvent<HTMLInputElement>) => handleItemChange(index, 'quantity', parseInt(e.target.value))}
        required
        type="number"
        min="1"
      />
      {showRemoveButton && (
        <>
          <button
            type="button"
            className={`${styles.button} ${styles.deleteButton}`}
            onClick={() => removeItem(index)}
          >
            Remove
          </button>
        </>
      )}
    </div>
  );
};