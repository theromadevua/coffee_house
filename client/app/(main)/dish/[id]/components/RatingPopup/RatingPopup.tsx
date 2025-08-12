'use client';
import { useState } from 'react';
import styles from './ratingPopup.module.css';

interface RatingPopupProps {
  dishId: number;
  onClose: () => void;
  onSubmit: (rating: number) => void;
}

export const RatingPopup = ({ dishId, onClose, onSubmit }: RatingPopupProps) => {
  const [rating, setRating] = useState(0);
  const [hoverRating, setHoverRating] = useState(0);

  // Обработчик клика по overlay - закрывает попап и останавливает всплытие
  const handleOverlayClick = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    onClose();
  };

  // Обработчик клика по самому попапу - останавливает всплытие, но не закрывает
  const handlePopupClick = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
  };

  return (
    <div className={styles.overlay} onClick={handleOverlayClick}>
      <div className={styles.popup} onClick={handlePopupClick}>
        <h2 className={styles.title}>Rate this Dish</h2>
        <div className={styles.stars}>
          {[1, 2, 3, 4, 5].map((star) => (
            <button
              key={star}
              type="button"
              onClick={() => setRating(star)}
              onMouseEnter={() => setHoverRating(star)}
              onMouseLeave={() => setHoverRating(0)}
              className={`${styles.star} ${star <= (hoverRating || rating) ? styles.active : ''}`}
            >
              ★
            </button>
          ))}
        </div>
        <div className={styles.actions}>
          <button type="button" onClick={onClose} className={styles.cancel}>Cancel</button>
          <button
            type="button"
            onClick={() => rating > 0 && onSubmit(rating)}
            disabled={rating === 0}
            className={`${styles.submit} ${rating === 0 ? styles.disabled : ''}`}
          >
            Submit
          </button>
        </div>
      </div>
    </div>
  );
};