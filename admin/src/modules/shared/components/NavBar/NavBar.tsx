import { Navigate, NavLink } from 'react-router-dom';
import styles from '../../styles/navBar.module.css';
import useNavBar from './hooks/useNavBar';
import { ConfirmationDialog } from '../ConfirmationDialog/ConfirmationDialog';

const NavBar = () => {
  const { user, navItems, handleNavigate, isConfirmOpen, setIsConfirmOpen, handleLogout } = useNavBar();

  if (!user) {
    return <Navigate to="/login" />;
  }

  return (
    <div className={styles.navBar}>
      <NavLink to="/admin">
        <h1>Coffee House</h1>
      </NavLink>

      <div className={styles.navBarContent}>
        {navItems.map((item) => (
          <NavLink
            key={item.path}
            to={item.path}
            className={({ isActive }) => 
              isActive ? `${styles.navLink} ${styles.active}` : styles.navLink
            }
            onClick={handleNavigate(item.path)}
          >
            {item.label}
          </NavLink>
        ))}
        <button className={styles.buttonRed} onClick={() => setIsConfirmOpen(true)}>
          Logout
        </button>
      </div>

      <ConfirmationDialog
        open={isConfirmOpen}
        onClose={() => setIsConfirmOpen(false)}
        onConfirm={handleLogout}
        title="Confirm"
        message={`Are you sure you want to logout?`}
      />
    </div>
  );
};

export default NavBar;