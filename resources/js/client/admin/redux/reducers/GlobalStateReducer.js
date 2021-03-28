import Types from "../Types";

export const initialState = {
    
}

export const GlobalReducer = (state = initialState, action) => {
    switch (action.type) {
        case Types.INITIALIZE_GLOBAL_STATE: {
            state = action.payload;
            return state;
        }
        case Types.SET_GLOBAL_STATE: {
            return { 
                ...state, 
                ...action.payload
            };
        }
        case Types.SAVE_API_TOKEN: {
            const {apiToken} = action.payload;
            localStorage.setItem('apiToken', apiToken);
            state.apiToken = apiToken;
            
            return state;
        }
        case Types.REMOVE_API_TOKEN: {
            localStorage.removeItem("apiToken");
            state.apiToken = null;

            return state;
        }
        default:
            return state;
    }
}

export default GlobalReducer;