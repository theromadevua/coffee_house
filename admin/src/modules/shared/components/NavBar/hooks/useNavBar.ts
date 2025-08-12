import { useNavigate } from 'react-router-dom';
import useAuthStore from '../../../../../store/authStore';
import { useState } from 'react';

interface NavItem {
  label: string;
  path: string;
}

const useNavBar = () => {
  const { user, logout } = useAuthStore();
  const [isConfirmOpen, setIsConfirmOpen] = useState<boolean>(false)
  const navigate = useNavigate();

  const navItems: NavItem[] = [
    { label: 'Statistic', path: '/statistic' },
    { label: 'Carts', path: '/carts' },
    { label: 'Dishes', path: '/dishes' },
    { label: 'Orders', path: '/orders' },
    { label: 'Categories', path: '/categories' },
    { label: 'Users', path: '/users' },
    { label: 'Profile', path: '/admin' },
  ];

  const handleNavigate = (path: string) => () => {
    navigate(path);
  };

  const handleLogout = () => {
    logout();
  };

  return {
    user,
    navItems,
    isConfirmOpen,
    handleNavigate,
    handleLogout,
    setIsConfirmOpen
  };
};

export default useNavBar;