import React, {useState, useEffect} from "react"
import {useHistory} from "react-router-dom";
import {useAuthHook} from "../../hooks/auth.hook";

export const Login = ({logInHandler}) => {

    const history = useHistory()
    const {login, authError} = useAuthHook()
    const [form, setForm] = useState({
        email: '', password: ''
    })
    const [errorMessage, setErrorMessage] = useState([])
    const [formSubmitting, setFormSubmitting] = useState(false)

    useEffect(() => {
        if (authError.length > 0) {
            setErrorMessage(authError)
            setFormSubmitting(false)

        }
    }, [authError])

    const changeHandler = event => {
        setForm({...form, [event.target.name]: event.target.value})
    }

    const submitForm = async () => {
        setFormSubmitting(true)
        await login(JSON.stringify(form))
        if (localStorage.getItem('appState') != null) {
            logInHandler()
            history.push('/flats')
        }
    }

    return (
        <div className="row justify-content-center mt-5">

            <div className="col-md-4 ">
                {errorMessage.map((message, idx) => (
                    <p
                        key={idx}
                        style={{color: 'red', marginBottom: '3px'}}
                    >
                        {message}
                    </p>
                ))}
                <form>
                    <div className="form-group">
                        <label htmlFor="InputEmail">Электронная почта</label>
                        <input
                            type="email"
                            name="email"
                            className="form-control"
                            id="InputEmail"
                            aria-describedby="emailHelp"
                            placeholder="Введите электронную почту"
                            onChange={changeHandler}
                        />
                    </div>
                    <div className="form-group">
                        <label htmlFor="InputPassword">Пароль</label>
                        <input
                            type="password"
                            name="password"
                            className="form-control"
                            id="InputPassword"
                            placeholder="Введите пароль"
                            onChange={changeHandler}
                        />
                    </div>
                    {formSubmitting ?
                        <button
                            type="button"
                            className="btn btn-primary"
                            onClick={submitForm}
                            disabled
                        >
                            Отправить
                        </button>
                        :
                        <button
                            type="button"
                            className="btn btn-primary"
                            onClick={submitForm}
                        >
                            Отправить
                        </button>
                    }
                </form>
            </div>
        </div>
    )
}
