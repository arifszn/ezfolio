import Types from "../Types";

export const initialState = {
    
}

export const GlobalReducer = (state = initialState, action) => {
    switch (action.type) {
        case Types.INITIALIZE_GLOBAL_STATE:
            state = action.payload;
            return state;
        case Types.SET_GLOBAL_STATE:
            return { 
                ...state, 
                ...action.payload
            };
        default:
            return state;
    }
}

export default GlobalReducer;