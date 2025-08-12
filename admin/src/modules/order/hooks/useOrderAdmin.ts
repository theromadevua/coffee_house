import { useEffect } from 'react';
import { useOrderStore } from '../../../store/orderStore';
import { IOrder } from '../../shared/interfaces/order/IOrder';
import { useSearch } from '../../shared/hooks/useSearch';
import { useAdmin } from '../../shared/hooks/useAdmin';

export const useOrderAdmin = () => {
  const { orders, isLoading, error, fetchAllOrders, deleteOrder } = useOrderStore();

  useEffect(() => {
    fetchAllOrders();
  }, [fetchAllOrders]);

  const { searchQuery, handleSearch, filteredData: filteredOrders } = useSearch<IOrder>({
    data: orders,
    searchKeys: ['delivery_address', 'contact_phone'],
  });

  const crudHandlers = useAdmin<IOrder>(deleteOrder);

  return {
    isLoading,
    error,
    filteredOrders,
    searchQuery,
    handleOrderSearch: handleSearch,
    ...crudHandlers,
  };
};