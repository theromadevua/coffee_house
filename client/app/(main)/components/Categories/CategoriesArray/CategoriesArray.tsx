import styles from '../categories.module.css';

const CategoriesArray = ({categories, activeCategory, toggleCategory, clearCategory}: any) => {
    return <div className={styles.categoryList}>
    <div
      key={0}
      className={`${styles.categoryCard} ${activeCategory === '0' ? styles.active : ''}`}
      onClick={() => clearCategory()}
    >
      <h3>all</h3>
    </div>
    
    {categories.map((category: any) => (
      <div
        key={category.id}
        className={`${styles.categoryCard} ${activeCategory === category.id.toString() ? styles.active : ''}`}
        onClick={() => toggleCategory(category.id.toString())}
      >
        <h3>{category.name}</h3>
      </div>))}
  </div>
}

export default CategoriesArray