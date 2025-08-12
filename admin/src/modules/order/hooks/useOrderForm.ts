import { useState, useEffect, useCallback } from 'react';
import { useOrderStore } from '../../../store/orderStore';
import { useDishStore } from '../../../store/dishStore';
import { IOrder } from '../../shared/interfaces/order/IOrder';
import { ICreateOrderData } from '../../shared/interfaces/order/ICreateOrderData';
import { IUpdateOrderData } from '../../shared/interfaces/order/IUpdateOrderData';
import { OrderStatusEnum } from '../../shared/enums/order/OrderStatusEnum';

interface UseOrderFormProps {
  orderToEdit: IOrder | null;
  onClose: () => void;
}

interface FormOrderItem {
  dish_id: number;
  quantity: number;
  price: number;
}

export const useOrderForm = ({ orderToEdit, onClose }: UseOrderFormProps) => {
  const { createOrder, updateOrder } = useOrderStore();
  const { dishes, fetchDishes } = useDishStore();

  const [deliveryAddress, setDeliveryAddress] = useState('');
  const [contactPhone, setContactPhone] = useState('');
  const [deliveryTime, setDeliveryTime] = useState<string>(
    new Date().toISOString().slice(0, 16)
  );
  const [status, setStatus] = useState<OrderStatusEnum>(OrderStatusEnum.NEW);
  const [items, setItems] = useState<FormOrderItem[]>([
    { dish_id: 0, quantity: 1, price: 0 },
  ]);
  const [totalAmount, setTotalAmount] = useState(0);
  const [error, setError] = useState<string | null>(null);

  const isEditMode = !!orderToEdit;

  useEffect(() => {
    fetchDishes();
  }, [fetchDishes]);

  useEffect(() => {
    const newTotal = items.reduce((acc, item) => acc + item.price * item.quantity, 0);
    setTotalAmount(newTotal);
  }, [items]);

  useEffect(() => {
    if (orderToEdit) {
      setDeliveryAddress(orderToEdit.delivery_address || '');
      setContactPhone(orderToEdit.contact_phone || '');
      setDeliveryTime(
        orderToEdit.delivery_time
          ? new Date(orderToEdit.delivery_time).toISOString().slice(0, 16)
          : new Date().toISOString().slice(0, 16)
      );
      setStatus(orderToEdit.status || OrderStatusEnum.NEW);
      setItems(
        orderToEdit.items.length > 0
          ? orderToEdit.items.map((item) => ({
              dish_id: item.dish_id,
              price: item.price || dishes.find((d) => d.id === item.dish_id)?.price || 0,
              quantity: item.pivot?.quantity || item.quantity || 1,
            }))
          : [{ dish_id: 0, quantity: 1, price: 0 }]
      );
    } else {
      setDeliveryAddress('');
      setContactPhone('');
      setDeliveryTime(new Date().toISOString().slice(0, 16));
      setStatus(OrderStatusEnum.NEW);
      setItems([{ dish_id: 0, quantity: 1, price: 0 }]);
    }
  }, [orderToEdit, dishes]);

  const handleItemChange = useCallback(
    (index: number, field: 'dish_id' | 'quantity', value: number) => {
      const newItems = [...items];
      const currentItem = { ...newItems[index] };

      if (field === 'dish_id') {
        currentItem.dish_id = value;
        const selectedDish = dishes.find((d) => d.id === value);
        currentItem.price = selectedDish ? selectedDish.price : 0;
      } else if (field === 'quantity') {
        currentItem.quantity = value > 0 ? value : 1;
      }

      newItems[index] = currentItem;
      setItems(newItems);
    },
    [items, dishes]
  );

  const addItem = useCallback(() => {
    setItems((prevItems) => [...prevItems, { dish_id: 0, quantity: 1, price: 0 }]);
  }, []);

  const removeItem = useCallback((index: number) => {
    setItems((prevItems) => prevItems.filter((_, i) => i !== index));
  }, []);

  const validateForm = useCallback(() => {
    if (!deliveryAddress || !contactPhone) {
      setError('Please fill in all required fields (delivery address and contact phone).');
      return false;
    }
    const validItems = items.filter((item) => item.dish_id > 0 && item.quantity > 0);
    if (validItems.length === 0) {
      setError('Please add at least one valid item with a dish and quantity.');
      return false;
    }
    setError(null);
    return true;
  }, [deliveryAddress, contactPhone, items]);

  const handleSubmit = useCallback(
    async (e: React.FormEvent) => {
      e.preventDefault();
      if (!validateForm()) {
        return;
      }

      const validItems = items.filter((item) => item.dish_id > 0 && item.quantity > 0);
      const itemsForApi = validItems.map(({ dish_id, quantity }) => ({
        dish_id,
        quantity,
      }));

      try {
        if (isEditMode && orderToEdit) {
          const updateData: IUpdateOrderData = {
            delivery_address: deliveryAddress,
            contact_phone: contactPhone,
            delivery_time: deliveryTime,
            status,
            items: itemsForApi,
          };
          await updateOrder(orderToEdit.id, updateData);
        } else {
          const createData: ICreateOrderData = {
            delivery_address: deliveryAddress,
            contact_phone: contactPhone,
            total_amount: totalAmount,
            items: itemsForApi,
            delivery_time: deliveryTime,
          };
          await createOrder(createData);
        }
        onClose();
      } catch (err) {
        setError('Failed to save the order. Please try again.');
        console.error('Order submission error:', err);
      }
    },
    [
      isEditMode,
      orderToEdit,
      deliveryAddress,
      contactPhone,
      deliveryTime,
      status,
      items,
      totalAmount,
      createOrder,
      updateOrder,
      onClose,
      validateForm,
    ]
  );

  return {
    deliveryAddress,
    contactPhone,
    deliveryTime,
    status,
    items,
    totalAmount,
    isEditMode,
    dishes,
    error,
    setDeliveryAddress,
    setContactPhone,
    setDeliveryTime,
    setStatus,
    handleItemChange,
    addItem,
    removeItem,
    handleSubmit,
  };
};