'use client';
import { useEffect, useCallback } from 'react';
import { useDishStore } from '../../../../store/dishStore';
import Item from '../Item/Item';
import styles from './items.module.css';
import { useQueryManager } from '../../hooks/useQueryManager';

const Items = () => {
  const { dishes, isLoading, error, fetchDishes, currentPage, lastPage } = useDishStore();
  const { activeCategory, page, changePage } = useQueryManager();

  const handleFetchDishes = useCallback(() => {
    fetchDishes(activeCategory || undefined, page);
  }, [fetchDishes, activeCategory, page]);

  useEffect(() => {
    handleFetchDishes();
  }, [handleFetchDishes]);

  const renderMockItems = () => {
    const mockItemsCount = 6; 
    return Array.from({ length: mockItemsCount }).map((_, index) => (
      <div key={`mock-${index}`} className={`${styles.coffeeItem} ${styles.mockItem}`}>
        <div className={styles.itemImage}>
          <div className={styles.placeholderImage}></div>
        </div>
        <div className={styles.itemContent}>
          <div className={styles.itemName}></div>
          <div className={styles.itemDescription}>
            <div className={styles.mockLine}></div>
            <div className={styles.mockLine}></div>
          </div>
          <div className={styles.itemFooter}>
            <div className={styles.itemPrice}></div>
            <div className={styles.itemActions}>
              <div className={styles.cartIcon}></div>
              <div className={styles.category}></div>
            </div>
          </div>
        </div>
      </div>
    ));
  };

  if (error) {
    return <div>Error: {error}</div>;
  }

  return (
    <div>
      <div className={styles.itemsContainer}>
        {isLoading ? (
          renderMockItems()
        ) : (
          dishes && dishes.map((dish: any) => (
            <Item
              key={dish.id}
              id={dish.id}
              name={dish.name}
              description={dish.description}
              price={dish.price}
              image_path={dish.gallery && dish?.gallery.images[0]?.path}
              category={dish?.category}
              ratings_avg_rating={dish?.ratings_avg_rating}
              isRated={dish.isRated}
            />
          ))
        )}
      </div>
      {dishes.length == 0 && <div>
        There is no items in this category.
      </div>}
      {lastPage > 0 && (
        <div className={styles.pagination}>
          <button
            onClick={() => changePage(currentPage - 1)}
            disabled={currentPage === 1}
            className={`${styles.pageButton} ${currentPage === 1 ? styles.disabled : ''}`}
          >
            Prev
          </button>
          {Array.from({ length: lastPage }, (_, index) => index + 1).map((pageNum) => (
            <button
              key={pageNum}
              onClick={() => changePage(pageNum)}
              className={`${styles.pageButton} ${currentPage === pageNum ? styles.active : ''}`}
            >
              {pageNum}
            </button>
          ))}
          <button
            onClick={() => changePage(currentPage + 1)}
            disabled={currentPage === lastPage}
            className={`${styles.pageButton} ${currentPage === lastPage ? styles.disabled : ''}`}
          >
            Next
          </button>
        </div>
      )}
    </div>
  );
};

export default Items;