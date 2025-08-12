import React, { useEffect } from 'react';
import { useOrderStore } from '../../../store/orderStore';
import styles from '../styles/StatisticPage.module.css';
import { AdminTable } from '../../shared/components/AdminTable/AdminTable';
import OrdersByDayChart from '../components/OrdersByDayChart';

interface StatisticPageProps {}

const StatisticPage: React.FC<StatisticPageProps> = () => {
  const { ordersStatistics, getOrdersStatistics, isLoading, error } = useOrderStore();

  useEffect(() => {
    getOrdersStatistics();
  }, [getOrdersStatistics]);

  if (isLoading) {
    return (
      <div className={styles.stateContainer}>
        <div className={styles.loadingSpinner}></div>
      </div>
    );
  }

  if (error) {
    return (
      <div className={styles.stateContainer}>
        <div className={styles.errorMessage}>Error: {error}</div>
      </div>
    );
  }

  if (!ordersStatistics) {
    return (
      <div className={styles.stateContainer}>
        <div className={styles.noDataMessage}>No statistics available</div>
      </div>
    );
  }

  const dishPopularityColumns = [
    {
      header: 'Rank',
      accessor: (_: any, index: number = 0) => index + 1,
    },
    {
      header: 'Dish Name',
      accessor: (dish: any) => dish.name,
    },
    {
      header: 'Total Ordered',
      accessor: (dish: any) => dish.total_ordered,
    },
  ];

  const topUsersColumns = [
    {
      header: 'Rank',
      accessor: (_: any, index: number = 0) => index + 1,
    },
    {
      header: 'User Name',
      accessor: (user: any) => user.name,
    },
    {
      header: 'Orders Placed',
      accessor: (user: any) => user.order_count,
    },
  ];

  return (
    <div className={styles.container}>
      <header>
        <h1 className={styles.pageTitle}>Order Statistics</h1>
      </header>

      <section className={styles.statBox}>
        <h2 className={styles.statBoxTitle}>Total Dishes Ordered</h2>
        <p className={styles.statBoxValue}>{ordersStatistics.total_dishes_ordered}</p>
      </section>

      <AdminTable
        title="Dish Popularity"
        data={ordersStatistics.dish_popularity}
        columns={dishPopularityColumns}
      />

      <AdminTable
        title="Top Users"
        data={ordersStatistics.top_users}
        columns={topUsersColumns}
      />

      <OrdersByDayChart ordersByDay={ordersStatistics.orders_by_day} />
    </div>
  );
};

export default StatisticPage;