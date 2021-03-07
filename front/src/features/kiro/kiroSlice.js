import { createSlice } from '@reduxjs/toolkit';

// Slice

export const kiroSlice = createSlice({
  name: 'kiro',
  initialState: {
    currentView: 'Dashboard',
  },

  reducers: {
    changeView: (state, action) => {
      state.currentView = action.payload;
    },
  },
})

// Actions

export const { changeView } = kiroSlice.actions;

// Thunks

// Selectors

export const selectCurrentView = state => state.kiro.currentView;

// Reducer

export const kiroReducer = kiroSlice.reducer;