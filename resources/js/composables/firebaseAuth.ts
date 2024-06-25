import {
  getAuth,
  signOut as firebaseSignOut,
  type User,
  createUserWithEmailAndPassword,
  signInWithEmailAndPassword,
  AuthErrorCodes,
} from 'firebase/auth'
import { ref } from 'vue'
import firebaseApp from '@/config/firebase';
import { FirebaseError } from 'firebase/app';

const firebaseUser = ref<User | null>(null);
const errorText = ref('');
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
    errorText.value = '';
    try {
      const userCredential = await createUserWithEmailAndPassword(
        auth,
        email,
        password
      );
      firebaseUser.value = userCredential.user;
      return true;
    } catch (e) {
      await signOut();
      return false;
    }
  }

  const signIn = async (email: string, password: string) => {
    errorText.value = '';
    try {
      const userCredential = await signInWithEmailAndPassword(
        auth,
        email,
        password
      );
      firebaseUser.value = userCredential.user;
      return true;
    } catch (e) {
      if (e instanceof FirebaseError) {
        console.log(e.code, AuthErrorCodes.INVALID_LOGIN_CREDENTIALS, e.code === AuthErrorCodes.INVALID_LOGIN_CREDENTIALS);
        if (e.code === AuthErrorCodes.INVALID_LOGIN_CREDENTIALS) {
          errorText.value = 'メールアドレスまたはパスワードが間違っています';
        }
      }
      await signOut();
      return false;
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
    errorText,
    firebaseUser,
  }
}
