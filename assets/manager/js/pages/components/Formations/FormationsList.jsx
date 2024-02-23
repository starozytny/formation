import React from "react";
import PropTypes from 'prop-types';

import { Alert } from "@commonComponents/Elements/Alert";

import { FormationsItem } from "@managerPages/Formations/FormationsItem";

export function FormationsList ({ data, highlight, onDelete }) {
    return <div className="list">
        <div className="list-table">
            <div className="items items-formations">
                <div className="item item-header">
                    <div className="item-content">
                        <div className="item-infos">
                            <div className="col-1">Date</div>
                            <div className="col-2">Formation</div>
                            <div className="col-3">Informations</div>
                            <div className="col-4">Accès</div>
                            <div className="col-5 actions" />
                        </div>
                    </div>
                </div>

                {data.length > 0
                    ? data.map((elem) => {
                        return <FormationsItem key={elem.id} elem={elem} highlight={highlight} onDelete={onDelete} />;
                    })
                    : <Alert>Aucune donnée enregistrée.</Alert>
                }
            </div>
        </div>
    </div>
}

FormationsList.propTypes = {
    data: PropTypes.array.isRequired,
    onDelete: PropTypes.func.isRequired,
}
