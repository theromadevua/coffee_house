'use client';
import { useEffect, useState } from 'react';
import { FaCoffee, FaHome, FaShoppingCart, FaUser, FaFile, FaSignInAlt, FaBars } from 'react-icons/fa';
import Link from 'next/link';
import styles from './navBar.module.css';
import useAuthStore from '@/store/authStore';
import { useCartStore } from '@/store/cartStore';
import { CircleUserRound, Home, LogIn, LogOut, Scroll, ShoppingCart, User } from 'lucide-react';

const NavBar = () => {
  const [isOpen, setIsOpen] = useState(false);
  const {items, fetchCart} = useCartStore();
  const {isAuthenticated, user} = useAuthStore();

  useEffect(() => {
    fetchCart();
  }, []);

  const toggleMenu = () => {
    setIsOpen(!isOpen);
  };

  return (
    <>
      <button 
        className={styles.mobileMenuButton}
        onClick={toggleMenu}
        aria-label="Toggle menu"
      >
        <FaBars size={24} />
      </button>

      <div className={`${styles.overlay} ${isOpen ? styles.open : ''}`} onClick={toggleMenu}></div>

      <nav className={`${styles.navbar} ${isOpen ? styles.open : ''}`}>
        <div className={styles.navbarHeader}>
          <Link href="/">
            <div className={styles.logo}>
              <FaCoffee className={styles.logoIcon} size={24} />
              <h2 className={styles.navBarTitle}>Coffee House</h2>
            </div>
          </Link>
        </div>
        <div className={styles.navbarNav}>
          <Link href="/" className={styles.navLink}>
            <Home className={styles.navIcon} />
            <span>Home</span>
          </Link>
          <Link href="/cart" className={styles.navLink}>
            <div className={styles.iconContainer}>
              {items.length > 0 && <div className={styles.cartActive}></div>}
              <ShoppingCart className={styles.navIcon} />
            </div>
            <span>Cart</span>
          </Link>
          <Link href="/orders" className={styles.navLink}>
            <Scroll className={styles.navIcon} />
            <span>Orders</span>
          </Link>
          {isAuthenticated ? 
            <Link href="/profile" className={styles.navLink}>
              <CircleUserRound className={styles.navIcon} />
              <span>Profile</span>
            </Link> :
            <Link href="/auth/login" className={`${styles.navLink} ${styles.loginLink}`}>
              <LogIn className={styles.navIcon} />
              <span>Login</span>
            </Link>
          }
        </div>
      </nav>
    </>
  );
};

export default NavBar;