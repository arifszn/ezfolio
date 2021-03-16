import { createStore, applyMiddleware } from "redux";
import { RootReducer, initialState } from './RootReducer';

const thunkMiddleware = require('redux-thunk').default;

export const ConfigureStore = () => {
  const store = createStore(
    RootReducer,
    initialState,
    applyMiddleware(thunkMiddleware)
  );

  return store;
};

export default ConfigureStore;