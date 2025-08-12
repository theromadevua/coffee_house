'use client';

import React, { useRef } from 'react';
import { useProfile } from '../../hooks/useProfile';
import ProfileWindow from '../ProfileWindow/ProfileWindow';
import styles from './profileForm.module.css';
import ProfileData from '../ProfileData/ProfileData';
import UpdateForm from '../ProfileUpdate/ProfileUpdate';

export default function ProfileForm() {
  const {
    user,
    formData,
    handleChange,
    handleLogout,
    handleSubmit,
    isRedirecting,
    profileImageWindow,
    handleOpenProfileImage,
    addImage,
    errors,
  } = useProfile();

  const fileInputRef = useRef<HTMLInputElement>(null);

  if (isRedirecting) return null;

  const triggerFileInput = () => {
    fileInputRef.current?.click();
  };

  const handleFileSelect = async (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (file) {
      await addImage(file);
    }
    if (fileInputRef.current) {
      fileInputRef.current.value = '';
    }
  };

  const hasImages = user?.gallery?.images && user.gallery.images.length > 0;

  return (
    <div className={styles.profileContainer}>
      {profileImageWindow && hasImages && <ProfileWindow />}

      <div className={styles.profileWrapper}>
        <h1 className={styles.profileUpdate}>Profile</h1>

        <ProfileData
          hasImages={hasImages}
          name={user?.name}
          email={user?.email}
          user={user}
          handleOpenProfileImage={handleOpenProfileImage}
          triggerFileInput={triggerFileInput}
          onLogout={handleLogout}
        />

        <input
          type="file"
          ref={fileInputRef}
          style={{ display: 'none' }}
          accept="image/*"
          onChange={handleFileSelect}
        />

        <h1 className={styles.profileUpdate}>Update Profile</h1>

        <UpdateForm
          formData={formData}
          onChange={handleChange}
          onSubmit={handleSubmit}
          errors={errors}
        />
      </div>
    </div>
  );
}