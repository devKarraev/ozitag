import React from "react"
import {NavLink} from "react-router-dom"
import "./Header.css"

export const Header = ({isLoggedIn, logOut}) => {

    return (
        <div className="Header">
            <nav className="navbar-light bg-light">
                <div className="navbar-nav">
                    <div className="row" style={{marginRight: '0px'}}>
                        <div className="col-sm-12 text-right">
                            <NavLink exact className="Link nav-item nav-link d-inline-block" to="/flats">Квартиры</NavLink>
                            {isLoggedIn
                                ?
                                <button
                                    onClick={() => logOut()}
                                    className="Link LogOut nav-item nav-link d-inline-block"
                                >
                                    Выйти
                                </button>
                                :
                                <React.Fragment>
                                    <NavLink exact className="Link nav-item nav-link d-inline-block" to="/login">Войти</NavLink>
                                    <NavLink exact className="Link nav-item nav-link d-inline-block" to="/register">Регистрация</NavLink>
                                </React.Fragment>
                            }
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    )
}
