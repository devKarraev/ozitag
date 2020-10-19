import React from "react"
import {Link} from "react-router-dom"
import "./NotFound.css"

export const NotFond = () => {
    return (
        <div className="NotFound">
            <h1>Страница не найдена</h1>
            <Link to="/flats">Перейти к просмотру квартир</Link>
        </div>
    )
}
