import {
  getAuth,
  signOut as firebaseSignOut,
  type User,
  createUserWithEmailAndPassword,
  signInWithEmailAndPassword,
} from 'firebase/auth'
import { ref } from 'vue'
import firebaseApp from '@/config/firebase';
import apiClient from '@/apiClient';

const firebaseUser = ref<User | null>(null);
const auth = getAuth(firebaseApp);

const initUser = () => {
  return new Promise<User | null>((resolve) => {
    const unsubscribe = auth.onAuthStateChanged((user) => {
      resolve(user);
      unsubscribe();
    });
  });
}
firebaseUser.value = await initUser();

auth.onAuthStateChanged((user) => {
  firebaseUser.value = user;
});

export const useAuth = () => {

  const signUp = async (email: string, password: string) => {
    try {
      const userCredential = await createUserWithEmailAndPassword(
        auth,
        email,
        password
      );
      firebaseUser.value = userCredential.user;
    } catch (e) {
      signOut();
    }
  }

  const signIn = async (email: string, password: string) => {
    try {
      const userCredential = await signInWithEmailAndPassword(
        auth,
        email,
        password
      );
      firebaseUser.value = userCredential.user;
    } catch (e) {
      signOut();
    }

  }

  const signOut = async () => {
    await firebaseSignOut(auth);
    firebaseUser.value = null;
  };


  return {
    signIn,
    signUp,
    signOut,
    firebaseUser,
  }
}
