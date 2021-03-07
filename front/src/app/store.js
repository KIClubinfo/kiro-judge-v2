import { configureStore } from '@reduxjs/toolkit';
import { kiroReducer } from "../features/kiro/kiroSlice";

export default configureStore({
  reducer: {
    kiro: kiroReducer,
  },
});
