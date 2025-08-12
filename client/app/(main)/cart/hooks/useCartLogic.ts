import { useState, useEffect } from 'react';
import { useCartStore } from '@/store/cartStore';
import { useOrderStore } from '@/store/orderStore';
import { useRouter } from 'next/navigation';

export interface CreateOrderData {
  delivery_address: string;
  contact_phone: string;
  delivery_time: string | null;
  status: string;
  items: { dish_id: number; quantity: number; price: number }[];
}

export const useCartLogic = () => {
  const {
    items,
    totalPrice,
    itemCount,
    isLoading: isCartLoading,
    error: cartError,
    fetchCart,
    updateItem,
    removeItem,
    clearCart,
  } = useCartStore();
  const {
    createOrder,
    isLoading: isOrderLoading,
    error: orderError,
  } = useOrderStore();
  const router = useRouter();
  const [quantities, setQuantities] = useState<Record<string, number>>({});
  const [deliveryAddress, setDeliveryAddress] = useState('');
  const [contactPhone, setContactPhone] = useState('');
  const [deliveryTime, setDeliveryTime] = useState('');
  const [formError, setFormError] = useState('');

  useEffect(() => {
    fetchCart();
  }, [fetchCart]);

  useEffect(() => {
    const initialQuantities = items.reduce((acc, item) => {
      acc[item.id] = item.quantity;
      return acc;
    }, {} as Record<string, number>);
    setQuantities(initialQuantities);
  }, [items]);

  const handleQuantityChange = (itemId: number, value: string) => {
    const newQuantity = parseInt(value, 10);
    if (!isNaN(newQuantity) && newQuantity > 0) {
      setQuantities((prev) => ({ ...prev, [itemId]: newQuantity }));
    }
  };

  const validateForm = () => {
    // Delivery Address validation
    if (!deliveryAddress.trim()) {
      return 'Delivery address is required.';
    }
    if (deliveryAddress.length < 10) {
      return 'Delivery address must be at least 10 characters long.';
    }

    const phoneRegex = /^\+?[\d\s-]{10,}$/;
    if (!contactPhone.trim()) {
      return 'Contact phone is required.';
    }
    if (!phoneRegex.test(contactPhone)) {
      return 'Please enter a valid phone number (e.g., +1234567890 or 123-456-7890).';
    }

    if (deliveryTime) {
      const deliveryDate = new Date(deliveryTime);
      const currentDate = new Date();
      if (isNaN(deliveryDate.getTime())) {
        return 'Please enter a valid delivery time.';
      }
      if (deliveryDate <= currentDate) {
        return 'Delivery time must be in the future.';
      }
    }

    return '';
  };

  const handlePlaceOrder = async (e: React.FormEvent) => {

    e.preventDefault();

    // Run validation
    const validationError = validateForm();
    if (validationError) {
      setFormError(validationError);
      return;
    }

    setFormError('');



    const orderData: CreateOrderData = {
      delivery_address: deliveryAddress,
      contact_phone: contactPhone,
      delivery_time: deliveryTime || null,
      status: 'processing',
      items: items.map((item) => ({
        dish_id: item.id,
        quantity: item.quantity,
        price: item.price,
      })),
    };

    try {
      await createOrder(orderData);
      clearCart();
    } catch (error) {
      setFormError('Failed to place order. Please try again.');
    }
  };

  return {
    items,
    totalPrice,
    itemCount,
    isCartLoading,
    cartError,
    orderError,
    quantities,
    handleQuantityChange,
    updateItem,
    removeItem,
    deliveryAddress,
    setDeliveryAddress,
    contactPhone,
    setContactPhone,
    deliveryTime,
    setDeliveryTime,
    handlePlaceOrder,
    isOrderLoading,
    formError,
  };
};