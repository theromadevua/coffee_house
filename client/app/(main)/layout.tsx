import NavBar from "./components/NavBar/NavBar";
import Footer from "./components/Footer/Footer";
import styles from './styles/layout.module.css';

export default async function Layout({ children }: { children: React.ReactNode }) {
  return (
    <div className={styles.layoutContainer}>
      <NavBar />
      <div className={styles.layoutContentContainer}>
        <main className={styles.layoutContent}>
          {children}
        </main>
        <Footer />
      </div>
    </div>
  );
}
