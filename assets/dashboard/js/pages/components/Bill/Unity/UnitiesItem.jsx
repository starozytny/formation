import React, { Component } from 'react';

import { ButtonIcon } from "@dashboardComponents/Tools/Button";

export class UnitiesItem extends Component {
    render () {
        const { elem, onChangeContext, onDelete } = this.props;

        return <div className="item">
            <div className="item-content">
                <div className="item-body">
                    <div className="infos infos-col-2">
                        <div className="col-1">
                            <div className="name">{elem.name}</div>
                        </div>
                        <div className="col-2 actions">
                            {elem.isNatif ? <>
                                <div className="badge badge-0">Natif</div>
                            </> : <>
                                <ButtonIcon icon="pencil" onClick={() => onChangeContext("update", elem)}>Modifier</ButtonIcon>
                                <ButtonIcon icon="trash" onClick={() => onDelete(elem)}>Supprimer</ButtonIcon>
                            </>}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    }
}
