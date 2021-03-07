import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { changeView, selectCurrentView } from "../kiro/kiroSlice";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faHome, faTachometerAlt, faAddressBook, faListOl, faUser } from '@fortawesome/free-solid-svg-icons'
import styles from './menu.module.css';

export function Menu() {
    const currentView = useSelector(selectCurrentView);

    return (
        <section className='menu'>
            <div id='logo'> Insert logo here </div>
            <div id='top'>{currentView}</div>
            <div className='mid'>
                <ViewSelectButton name='Index' label='Accueil' icon={faHome} />
                <ViewSelectButton name='Dashboard' icon={faTachometerAlt} />
                <ViewSelectButton name='Contact' icon={faAddressBook} />
                <ViewSelectButton name='Leaderboard' icon={faListOl} />
                <ViewSelectButton name='Teams' icon={faUser} />
            </div>
            <div id='bot'></div>
        </section>
    );
}

function ViewSelectButton(props) {
    const dispatch = useDispatch();

    let viewLabel = props.name;
    let displayLabel = props.hasOwnProperty('label') ? props.label : props.name;

    if (props.hasOwnProperty('icon')) {
        return (
            <div className='mid-element'>
                <button onClick={() => {
                    dispatch(changeView(viewLabel));
                }}
                >
                    <FontAwesomeIcon icon={props.icon} />
                    <div className='mid-element-label'>{displayLabel}</div>
                </button>
            </div>
        );
    }

    return (
        <div className='mid-element'>
            <button onClick={() => {
                dispatch(changeView(viewLabel));
            }}
            >
                {displayLabel}
            </button>
        </div>
    );
}

function makeViewSelectButton(name) {
    return (
        <ViewSelectButton name={name} />
    );
}

