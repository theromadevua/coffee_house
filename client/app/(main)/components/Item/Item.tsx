'use client';

import Link from 'next/link';
import { useCategoryQuery } from '@/app/(main)/hooks/useCategoryQuery';
import styles from './item.module.css';
import { CircleOff, Hamburger, Star } from 'lucide-react';
import { useState, useEffect } from 'react';
import { FaShoppingCart } from 'react-icons/fa';
import { useCartStore } from '@/store/cartStore';
import { RatingPopup } from '../../dish/[id]/components/RatingPopup/RatingPopup';
import { useDishStore } from '@/store/dishStore';
import useAuthStore from '@/store/authStore';

interface ItemProps {
  id: number;
  name: string;
  description: string;
  price: number;
  image_path?: string;
  category?: any;
  ratings_avg_rating?: number;
  isRated?: boolean;
}

const preloadImage = (src: string): Promise<void> => {
  return new Promise((resolve, reject) => {
    const img = new Image();
    img.src = src;
    img.onload = () => resolve();
    img.onerror = () => reject();
  });
};

const Item = ({ id, name, description, price, image_path, category, ratings_avg_rating, isRated }: ItemProps) => {
  const { toggleCategory } = useCategoryQuery();
  const [imageLoaded, setImageLoaded] = useState(false);
  const [imageError, setImageError] = useState(false);
  const { addItem } = useCartStore();
  const {rateDish} = useDishStore()
  const [notification, setNotification] = useState<string | null>(null);
  const [showRatingPopup, setShowRatingPopup] = useState(false)
  const {isAuthenticated} = useAuthStore()

  useEffect(() => {
    if (image_path) {
      preloadImage(image_path)
        .then(() => setImageLoaded(true))
        .catch(() => setImageError(true));
    } else {
      setImageError(true);
    }
  }, [image_path]);

  const handleCategoryClick = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    if (category?.id) {
      toggleCategory(category.id.toString());
    }
  };

  const handleAddToCart = async (e: any) => {
    e.stopPropagation();
    e.preventDefault();
    const { success } = await addItem(id, 1);
    if (success) {
        setNotification(`${name} (x${1}) added to cart!`);
        setTimeout(() => setNotification(null), 3000);
    }
};


  const handleRateDish = async (rating: number) => {
    await rateDish(id, rating);
    setShowRatingPopup(false);
  };

  const handleRatingClick = (e: React.MouseEvent) => {
    e.preventDefault(); 
    e.stopPropagation();
    if(!isAuthenticated) return;
    setShowRatingPopup(true);
  };
  
  const handlePopupClose = () => {
    setShowRatingPopup(false);
  };

  return (
    <Link href={`/dish/${id}`} passHref>
      {showRatingPopup && (
        <RatingPopup 
          dishId={id} 
          onClose={handlePopupClose} 
          onSubmit={handleRateDish}
        />
      )}
      {notification && <div className={styles.notification}>{notification}</div>}
      <div className={styles.coffeeItem}>
        <div className={styles.itemImage}>

          {ratings_avg_rating? (
            <div onClick={handleRatingClick} className={styles.rating}>
              {Number(ratings_avg_rating).toFixed(1)}
              <Star size={16} style={{ fill: isRated ? 'var(--primary-800)' : 'none' }}/>
            </div>
          ) :
            <div onClick={handleRatingClick} className={styles.rating}>
              <CircleOff size={12}/>
              <Star size={16} style={{ fill: isRated ? 'var(--primary-800)' : 'none' }}/>
            </div>
          }


          {image_path && !imageError && imageLoaded ? (
            <img
              src={image_path}
              alt={name}
              className={styles.image}
            />
          ) : (
            <Hamburger
              className={styles.placeholderImage}
              size={100}
              color="#999"
              aria-label="Image not available or loading"
            />
          )}
        </div>
        <div className={styles.itemContent}>
          <h2 className={styles.itemName}>{name}</h2>
          <p className={styles.itemDescription}>{description}</p>
          <div className={styles.itemFooter}>
            <span className={styles.itemPrice}>${price}</span>
            <div className={styles.itemActions}>
              <FaShoppingCart className={styles.cartIcon} onClick={handleAddToCart}/>
              {category && (
                <div className={styles.category} onClick={handleCategoryClick}>
                  {category.name}
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </Link>
  );
};

export default Item;