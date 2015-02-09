LOCAL_PATH := $(call my-dir)

include $(CLEAR_VARS)

LOCAL_MODULE    := securekey
LOCAL_SRC_FILES := SecureKey.c

include $(BUILD_SHARED_LIBRARY)
