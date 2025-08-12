import Items from "../Items/Items";
import SearchBar from "../SearchBar/SearchBar";
import styles from './main.module.css'

const MainContainer = () => {
    
    return (
        <div className={styles.main}>
            <div className={styles.content}>
                <SearchBar/>
                <Items/>
            </div>
        </div>
    )
}

export default MainContainer;