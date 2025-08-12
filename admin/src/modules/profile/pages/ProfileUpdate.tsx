import React from 'react';
import styles from '../styles/profileUpdate.module.css';
import useProfileUpdate from '../hooks/useProfileUpdate';
import ImageSection from '../components/imageSection/ImageSection';
import InputField from '../../shared/components/formFields/InputField';
import SubmitButton from '../components/submitButton/SubmitButton';
import ImageGalleryModal from '../components/imageGallery/ImageGalleryModal';

const ProfileUpdate: React.FC = () => {
  const { 
    user, 
    formData, 
    isLoading, 
    error, 
    isGalleryOpen,
    fileInputRef,
    handleChange, 
    handleSubmit, 
    handleImageUpload,
    handleImageIconClick,
    handleAddImage,
    handleCloseGallery
  } = useProfileUpdate();

  if (!user) {
    return <div className={styles.container}>Please log in to update your profile</div>;
  }

  const stylesConfig = {
    input: styles.input,
    formGroup: styles.formGroup,
    label: styles.label
  };

  return (
    <div className={styles.container}>
      <h2 className={styles.title}>Update Profile</h2>
      <form onSubmit={handleSubmit} className={styles.form}>
        <ImageSection
          user={user}
          isLoading={isLoading}
          fileInputRef={fileInputRef}
          onIconClick={handleImageIconClick}
        />

        <input
          type="file"
          accept="image/*"
          onChange={handleImageUpload}
          className={styles.hiddenInput}
          disabled={isLoading}
          ref={fileInputRef}
        />

        <InputField
          id="name" 
          label="Name" 
          value={formData.name} 
          onChange={handleChange} 
          type="text" 
          stylesConfig={stylesConfig} 
        />
        <InputField 
          id="email" 
          label="Email" 
          value={formData.email} 
          onChange={handleChange} 
          type="email" 
          stylesConfig={stylesConfig} 
        />
        <InputField 
          id="password" 
          label="Password" 
          value={formData.password} 
          onChange={handleChange} 
          type="password" 
          stylesConfig={stylesConfig} 
        />
        <InputField 
          id="password_confirmation" 
          label="Password Confirmation" 
          value={formData.password_confirmation} 
          onChange={handleChange} 
          type="password" 
          stylesConfig={stylesConfig} 
        />

        {error && <div className={styles.error}>{error}</div>}
        <SubmitButton isLoading={isLoading} />
      </form>

      {isGalleryOpen && (
        <ImageGalleryModal
          images={user.gallery?.images || []}
          onClose={handleCloseGallery}
          onAddImage={handleAddImage}
        />
      )}
    </div>
  );
};

export default ProfileUpdate;