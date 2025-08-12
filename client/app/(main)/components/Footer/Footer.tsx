import styles from './footer.module.css';

const Footer = () => {
  return (
    <footer className={styles.footer}>
      <div className={styles.container}>
        <div className={styles.brand}>
          <h2 className={styles.logo}>Coffee House</h2>
          <p className={styles.tagline}>Brewed with love, served with warmth.</p>
        </div>
        <nav className={styles.nav}>
          <a href="/menu">Menu</a>
          <a href="/about">About Us</a>
          <a href="/locations">Locations</a>
          <a href="/contact">Contact</a>
        </nav>
        <div className={styles.info}>
          <p>&copy; {new Date().getFullYear()} Coffee House. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
