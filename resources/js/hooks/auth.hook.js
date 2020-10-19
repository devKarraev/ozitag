import {useState} from "react";

const storageName = 'appState'

export const useAuthHook = () => {

    const [authError, setAuthError] = useState([])
    const [authRegistered, setAuthRegistered] = useState(false)

    const login = async form => {
        try {
            const response = await fetch('/api/auth/login', {
                method: 'POST',
                body: form,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            })
            const data = await response.json()
            if (!response.ok) {
                setAuthError(['Некорректные данные'])
            } else {
                let userData = {
                    id: data.id,
                    name: data.name,
                    email: data.email,
                    access_token: data.access_token,
                };
                let appState = {
                    isLoggedIn: true,
                    user: userData
                };
                localStorage.setItem(storageName, JSON.stringify(appState))
            }
        } catch (e) {
            setAuthError(['Что-то пошло не так, попробуйте позже'])
        }
    }

    const register = async form => {
        try {
            const response = await fetch('api/auth/register', {
                method: 'POST',
                body: form,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            })
            const data = await response.json()
            if (!response.ok) {
                let messages = []
                for (let message in data.errors) {
                    messages.push(data.errors[message][0])
                }
                setAuthError(messages)
            } else {
                setAuthRegistered(true)
            }
        } catch (e) {
            setAuthError(['Что-то пошло не так, попробуйте позже'])
        }
    }

    const isUserLoggedIn = () => {
        let isUserLoggedIn = false
        if (localStorage.getItem(storageName) != null) {
            isUserLoggedIn = JSON.parse(localStorage.getItem('appState')).isLoggedIn
        }
        return isUserLoggedIn
    }

    return {login, authError, register, authRegistered, isUserLoggedIn}
}

