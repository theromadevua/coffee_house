import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import AdminLayout from './modules/shared/components/AdminLayout/AdminLayout';
import AuthProvider from './modules/shared/components/AuthProvider/AuthProvider';
import DishAdminPage from './modules/dish/pages/DishPage';
import StatisticPage from './modules/statistic/pages/StatisticPage';
import OrdersAdminPage from './modules/order/pages/OrderPage';
import CartAdminPage from './modules/cart/pages/CartPage';
import CategoryAdminPage from './modules/category/pages/CategoryPage';
import LoginPage from './modules/auth/pages/LoginPage';
import ProfileUpdate from './modules/profile/pages/ProfileUpdate';
import UsersPage from './modules/users/pages/UsersPage';
import './index.css'


function App() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <Routes>
            <Route path="/dishes" element={<AdminLayout><DishAdminPage /></AdminLayout>} />
            <Route path="/statistic" element={<AdminLayout><StatisticPage /></AdminLayout>} />
            <Route path="/orders" element={<AdminLayout><OrdersAdminPage /></AdminLayout>} />
            <Route path="/carts" element={<AdminLayout><CartAdminPage /></AdminLayout>} />
            <Route path="/categories" element={<AdminLayout><CategoryAdminPage /></AdminLayout>} />
            <Route path="/users" element={<AdminLayout><UsersPage /></AdminLayout>} />
            <Route path="/login" element={<LoginPage />} />
            <Route path="/admin" element={<AdminLayout><ProfileUpdate /></AdminLayout>}/>
            <Route path="*" element={<Navigate to="/admin" replace />} />
        </Routes>
      </AuthProvider>
    </BrowserRouter>
  );
}

export default App;