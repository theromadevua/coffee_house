import ProfileImage from "../ProfileImage/ProfileImage";
import styles from './ProfileData.module.css';


interface ProfileDataProps {
    name?: string;
    email?: string;
    onLogout: () => void;
    hasImages: boolean;
    user: any; 
    handleOpenProfileImage: () => any; 
    triggerFileInput: () => any; 
  }
  
  const ProfileData: React.FC<ProfileDataProps> = ({ name, email, onLogout, hasImages, user, handleOpenProfileImage, triggerFileInput}) => (
    <div className={styles.profileData}>
      <div className={styles.profileDataText}>
        <div className={styles.profileMainData}>
          <ProfileImage
            hasImages={hasImages}
            imageSrc={hasImages ? user.gallery.images[0].path : undefined}
            onClick={hasImages ? handleOpenProfileImage : triggerFileInput}
          />
          <div>
            <h1 className={styles.profileTitle}>{name}</h1>
            <p className={styles.profileSubtitle}>{email}</p>
          </div>        
  
        </div>
        <button className={styles.profileDataButton} onClick={onLogout}>
          Logout
        </button>
      </div>
    </div>
  );

  export default ProfileData;