import { Navigate } from 'react-router-dom';
import styles from './adminLayout.module.css'
import useAuthStore from '../../../../store/authStore';
import NavBar from '../NavBar/NavBar';

const AdminLayout = ({children}: {children: React.ReactNode}) => {
    const {isAuthenticated} = useAuthStore()

    if(!isAuthenticated){
        return <Navigate to="/login" replace />
    }

  return (
    <div className={styles.adminLayout}>
      <NavBar/>
      <div className={styles.content}>
        {children}
      </div>
    </div>
  );
};

export default AdminLayout;