import React, {useEffect, useState} from "react"
import {Pagination} from 'react-laravel-paginex'
import {Link} from "react-router-dom";
import "./Flats.css"
import {useAuthHook} from "../../../hooks/auth.hook";

const paginationOptions = {
    nextButtonText: 'Вперед',
    prevButtonText: 'Назад',
    numbersCountForShow: 2
}

export const Flats = () => {

    const {isUserLoggedIn} = useAuthHook()
    const [flats, setFlats] = useState([])
    const [paginationObject, setPaginationObject] = useState(null)
    const [isLoading, setIsLoading] = useState(true)
    const [priceOption, setPriceOption] = useState('DEFAULT')
    const [dateOption, setDateOption] = useState('DEFAULT')
    const [isLoggedIn, setIsLoggedIn] = useState(false)

    useEffect(() => {
        async function fetchFlats() {
            try {
                if (isUserLoggedIn()) {
                    setIsLoggedIn(isUserLoggedIn)
                }

                const response = await fetch('/api/posts')
                const flatsData = await response.json()

                setPaginationObject(flatsData)
                setIsLoading(false)
                setFlats(flatsData.data)


            } catch (e) {
                console.log('Something went wrong')
            }
        }
        fetchFlats()
    }, [])

    const getPricePage = async data => {
        try {
            const response = await fetch(`/api/posts?sort-price=${priceOption}&sort-date=${dateOption}&page=${data.page}`)
            const responseData = await response.json()

            setPaginationObject(responseData)
            setFlats(responseData.data)

            window.scrollTo(0, 0);
        } catch (e) {
            console.log('Something went wrong')
        }
    }

    const filterByPrice = async e => {
        setIsLoading(true)

        const currentOption = e.target.value
        setPriceOption(currentOption)

        try {
            const response = await fetch(`/api/posts?sort-price=${currentOption}&sort-date=${dateOption}&page=1`)
            const data = await response.json()

            setPaginationObject(data)
            setFlats(data.data)
            setIsLoading(false)
        } catch (e) {
            console.log('Something went wrong')
        }
    }

    const filterByDate = async e => {
        setIsLoading(true)

        const currentOption = e.target.value
        setDateOption(currentOption)

        try {
            const response = await fetch(`/api/posts?sort-price=${priceOption}&sort-date=${currentOption}&page=1`)
            const data = await response.json()

            setPaginationObject(data)
            setFlats(data.data)
            setIsLoading(false)
        } catch (e) {
            console.log('Something went wring')
        }
    }

    const deletePost = async e => {
        const postId = e.target.dataset.id
        const confirmation = confirm('Вы действительно хотите удалить запись?')

        if (confirmation) {
            try {
                const response = await fetch(`/api/posts/${postId}`, {
                    method: 'DELETE'
                })
                const data = await response.json()
                await getPricePage(paginationObject)
                alert(data)
            } catch (e) {
                console.log('Something went wrong')
            }
        }
    }

    return (
        <div>
            {flats.length > 0 &&
            <div className="filter">
                <span>Цена</span>
                <select
                    className="filter-price"
                    onChange={e => filterByPrice(e)}
                >
                    <option value='DEFAULT'>По умолчанию</option>
                    <option value='ASC'>По возрастанию</option>
                    <option value='DESC'>По убыванию</option>
                </select>
                <span>Дата</span>
                <select
                    className="filter-date"
                    onChange={e => filterByDate(e)}
                >
                    <option value='DEFAULT'>По умолчанию</option>
                    <option value='ASC'>Сначала старые</option>
                    <option value='DESC'>Cначала новые</option>
                </select>
            </div>
            }
            { isLoading &&
                <div className="lds-dual-ring"></div>
            }
            {flats.map((flat, idx) => {
                return (
                    <div
                        className="flat-post bg-light"
                        key={idx}
                    >
                        <h4>{flat.title}</h4>
                        <img width="300" src={flat.image_path} />
                        <div dangerouslySetInnerHTML={{__html: flat.post_status}} />
                        <h4 className="flat-price">{flat.price} руб/сутки</h4>
                        <div dangerouslySetInnerHTML={{__html: flat.main_content}} />
                        {isLoggedIn
                            ?
                            <React.Fragment>
                                <Link to={`/flats/${flat.id}`} type="button" className="btn btn-primary">Изменить</Link>
                                <button
                                    type="button"
                                    className="btn btn-danger"
                                    data-id={flat.id}
                                    onClick={e => deletePost(e)}
                                >
                                    Удалить
                                </button>
                            </React.Fragment>
                            :
                            ''
                        }

                    </div>
                )
            })}
            {paginationObject != null && paginationObject.last_page > 1
                ?
                <Pagination changePage={getPricePage} data={paginationObject} options={paginationOptions}/>
                :
                ''
            }
        </div>
    )
}
