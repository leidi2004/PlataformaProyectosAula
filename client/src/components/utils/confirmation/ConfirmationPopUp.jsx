// Styles
import styles from './ConfirmationPopUp.module.scss';
// Icon
import iconConfirmation from '../../../assets/img/icons/exclamation.svg';
// Dependencies
import { PropTypes } from 'prop-types';

export const ConfirmationPopUp = ({ message, visible, setVisible }) => {

	const handleClosePopUp = () => {
		setVisible((prevVisible)=>({...prevVisible, deactivateUserWarning: false}));
	};

	return (
		<div className={visible ? styles.coverOpacity : styles.coverHidden}>
			<div className={styles.card}>
				<div className={styles.card__circle}>
					<img
						src={iconConfirmation}
						alt='Icon Exclamation'
						className={styles.card__icon}
					/>
				</div>
				<p className={styles.card__textBold}>Advertencia</p>
				<p className={styles.card__textLight}>{message}</p>
				<div className={styles.card__buttonWrapper}>
					<button
						type='button'
						className={styles.card__buttonCancel}
						onClick={handleClosePopUp}
					>
						Cancelar
					</button>
                    <button type="button" className={styles.card__buttonAccept}>Aceptar</button>
				</div>
			</div>
		</div>
	);
};

ConfirmationPopUp.propTypes = {
	message: PropTypes.string,
	visible: PropTypes.obj,
	setVisible: PropTypes.func,
};