import { useEffect, useState } from 'react';
import { useCartStore } from '../../../store/cartStore';
import { ICart } from '../../shared/interfaces/cart/ICart';
import { useSearch } from '../../shared/hooks/useSearch';

const useCartAdmin = () => {
  const { carts, isLoading, error, fetchAllCarts, clearCart } = useCartStore();
  const [isConfirmOpen, setIsConfirmOpen] = useState(false);
  const [cartToDelete, setCartToDelete] = useState<ICart | null>(null);

  useEffect(() => {
    fetchAllCarts();
  }, [fetchAllCarts]);

  const { searchQuery: searchUserId, handleSearch: handleCartSearch, filteredData: filteredCarts } = useSearch<ICart>({
    data: carts,
    searchKeys: ['user_id']
  });

  const handleDelete = (cart: ICart) => {
    setCartToDelete(cart);
    setIsConfirmOpen(true);
  };

  const confirmDelete = async () => {
    if (cartToDelete) {
      await clearCart(); 
      await fetchAllCarts();
      setIsConfirmOpen(false);
      setCartToDelete(null);
    }
  };

  return {
    carts: filteredCarts,
    isLoading,
    error,
    isConfirmOpen,
    cartToDelete,
    searchUserId,
    handleDelete,
    confirmDelete,
    handleCartSearch,
    setIsConfirmOpen
  };
};

export default useCartAdmin;